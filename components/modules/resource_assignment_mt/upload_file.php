<?php
require "db_connect.php"; // Koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $projectCode = $_POST['project_code']; // Kode proyek
    $folderName = $_POST['folder_name']; // Nama folder
    $rowNumber = $_POST['row_number']; // Row keberapa
    $uploadBase = 'uploads/'; // Folder utama sebelum ke Google Drive

    // Ambil gd_id dari database berdasarkan project_code dan folder_name
    $stmt = $conn->prepare("SELECT gd_id FROM sa_project_detail WHERE project_code = ? AND folder_name = ?");
    $stmt->bind_param("ss", $projectCode, $folderName);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!$data) {
        echo json_encode(["status" => "error", "message" => "gd_id tidak ditemukan untuk proyek ini"]);
        exit;
    }

    $gdId = $data['gd_id'];

    // Simpan file ke folder sementara sebelum upload ke Google Drive
    $uploadDir = $uploadBase . $folderName . "/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = $folderName . "_" . $rowNumber . "_" . time() . "_" . basename($_FILES['file']['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
        // Simpan informasi file ke database
        $sql = "INSERT INTO uploaded_files (file_name, file_path, project_code, folder_name, gd_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $fileName, $filePath, $projectCode, $folderName, $gdId);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "file_name" => $fileName, "gd_id" => $gdId]);
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal menyimpan ke database"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal upload file"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded"]);
}
?>
