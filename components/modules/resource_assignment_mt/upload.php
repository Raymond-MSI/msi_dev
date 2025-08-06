<?php
// require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require '../../../google-drive-requirement.php'; // Pastikan file koneksi ke Google Drive sudah benar

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $project_code = $_POST['project_code'];
    $fileType = $_POST['fileType'];
    $rowNumber = $_POST['rowNumber'];
    $order_number = $_POST['order_number'];

    // Cari folder_id berdasarkan project_code dan fileType
    $query = "SELECT gd_id FROM sa_project_detail WHERE project_code = ? AND folder_name = ?";
    $folder_name = ($fileType === "mt_report_date" || $fileType === "preventive_mt_date" || $fileType === "pmr_date") ? "01. Report" : "02. Backup Config";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $project_code, $folder_name);
    $stmt->execute();

    $result = $stmt->get_result();
    $folder = $result->fetch_assoc();

    if (!$folder) {
        echo "Folder tidak ditemukan!";
        exit;
    }

    $folder_id = $folder['gd_id']; // ID folder Google Drive target

    // Inisialisasi Google Drive Client
    $client = getClient();
    $service = new Google_Service_Drive($client);

    // Upload File ke Google Drive
    $file = new Google_Service_Drive_DriveFile();
    $file->setName($_FILES['uploadFile']['name']);
    $file->setParents([$folder_id]);

    $content = file_get_contents($_FILES['uploadFile']['tmp_name']);

    $uploadedFile = $service->files->create($file, [
        'data' => $content,
        'mimeType' => $_FILES['uploadFile']['type'],
        'uploadType' => 'multipart'
    ]);

    // Ambil file ID dari Google Drive
    $file_id = $uploadedFile->getId();

    // Simpan ke database
    if ($fileType == "mt_report_date"){
        $namanya = "mt_report_date_$rowNumber";
    }
    if ($fileType == "preventive_mt_date"){
        $namanya = "preventive_mt_date_$rowNumber";
    }
    if ($fileType == "pmr_date"){
        $namanya = "pmr_date_$rowNumber";
    }
    if ($fileType == "backup_mt_date"){
        $namanya = "backup_mt_date_$rowNumber";
    }
    $query = "INSERT INTO sa_maintenance_date_kpi (project_code,order_number,maintenance_name_file, file_name, folder_id, file_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $project_code, $order_number, $namanya, $_FILES['uploadFile']['name'], $folder_id, $file_id);
    $stmt->execute();
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "file_id" => $file_id,
            "file_name" => $_FILES['uploadFile']['name']
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menyimpan ke database!"]);
    }
    exit;
} else {
    echo "Request tidak valid!";
}
?>
