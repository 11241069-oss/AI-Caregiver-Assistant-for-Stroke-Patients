<?php
// api/pasien.php

// 1. Pengaturan CORS (Wajib di paling atas)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Tangani preflight request (OPTIONS) dari browser
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 2. Koneksi ke Database
$host = "127.0.0.1";
$port = "3307";
$user = "root";
$pass = "";
$db   = "ai_caregiver_baru"; 

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi DB Gagal: " . $conn->connect_error]));
}

// 3. Logika CRUD
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

if ($method === 'GET') {
    // Ambil semua data pasien
    $result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
    $patients = [];
    while($row = $result->fetch_assoc()) { $patients[] = $row; }
    echo json_encode(["success" => true, "data" => $patients]);

} elseif ($method === 'POST') {
    // Tambah data pasien (Sekarang pakai 4 kolom: nama, usia, kondisi, status)
    $stmt = $conn->prepare("INSERT INTO patients (nama, usia, kondisi, status) VALUES (?, ?, ?, ?)");
    
    // PERHATIKAN: "siss" = string, integer, string, string
    $stmt->bind_param("siss", $data['nama'], $data['usia'], $data['kondisi'], $data['status']);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pasien berhasil ditambahkan"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menambah pasien: " . $stmt->error]);
    }
}
?>