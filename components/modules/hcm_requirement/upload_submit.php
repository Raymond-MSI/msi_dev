<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
global $DBHCM;
$DBHCM = 'sa_md_hcm';
include_once '../../../google-drive-requirement.php';

function createFolder($service, $folderName, $parentFolderId)
{
    // Check if the folder already exists within the parent folder
    $existingFolders = $service->files->listFiles(array(
        'q' => "mimeType='application/vnd.google-apps.folder' and name='$folderName' and '$parentFolderId' in parents",
        'spaces' => 'drive',
        'includeItemsFromAllDrives' => true,
        'supportsAllDrives' => true
    ));

    if (count($existingFolders->getFiles()) > 0) {
        // Folder already exists, return its ID
        return $existingFolders->getFiles()[0]->getId();
    } else {
        // Folder doesn't exist, create a new one
        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $folderName,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => array($parentFolderId)
        ));
        $folder = $service->files->create($fileMetadata, array(
            'fields' => 'id',
            'supportsAllDrives' => true
        ));
        return $folder->id;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $client = getDriveClient();
    $service = new Google_Service_Drive($client);

    // Get the folder name from the form submission
    $id = $_POST['id'];
    $projectCode = $_POST['project_code'];
    $projectName = $_POST['project_name'];
    $request_by = 'chrisheryanda@mastersystem.co.id';

    // Combine project code and project name to form folder name
    $folderName = $projectCode . '[' . $projectName . ']';

    // Parent folder ID where the new folder should be created (Shared drive ID)
    $parentFolderId = '0AJzA7f9oBrefUk9PVA';

    // Create or get the folder within the specified parent folder
    $folderId = createFolder($service, $folderName, $parentFolderId);

    // Get the original file name
    $originalFileName = $_FILES['file']['name'];

    // Prepend project code to the file name
    $newFileName = $projectCode . '_' . $originalFileName;

    // Upload the file to the created folder
    // Upload the file to the created folder
    $fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $newFileName, // Use the modified file name
        'parents' => array($folderId)
    ));

    $content = file_get_contents($_FILES['file']['tmp_name']);

    $result = $service->files->create($fileMetadata, array(
        'data' => $content,
        'mimeType' => $_FILES['file']['type'],
        'uploadType' => 'multipart',
        'fields' => 'id',
        'supportsAllDrives' => true
    ));

    // Add view permission to the file for the request_by email
    $permission = new Google_Service_Drive_Permission(array(
        'type' => 'user',
        'role' => 'reader', // Setting the role to 'reader' grants view-only access
        'emailAddress' => $request_by,
    ));

    $permissionResult = $service->permissions->create($result->id, $permission, array('supportsAllDrives' => true));

    // Insert information into the database
    $fileId = $result->id; // Assuming this is the file ID returned by Google Drive

    $insertQuery = "INSERT INTO `sa_hcm_notecv` (`id_note`, `project_code`, `file`, `notes`, `id_folderdrive`, `id_filedrive`, `entry_by`, `entry_date`) VALUES (NULL, '$projectCode', '$newFileName', NULL, '$folderId','$fileId', '$request_by', current_timestamp()) ";

    $connection = mysqli_connect("localhost", "root", "", "sa_md_hcm");

    if ($connection) {
        if (mysqli_query($connection, $insertQuery)) {
            mysqli_close($connection);
            header("Location: http://localhost/microservices/index.php?mod=hcm_requirement&act=editshare&id=$id&project_code=$projectCode&project_name=$projectName&submit=Submit");
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
            mysqli_close($connection);
        }
    } else {
        echo "Database connection error";
    }

    echo "File uploaded successfully. File ID: " . $result->id;
}
