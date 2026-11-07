<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=UTF-8");

// Jawab Preflight Browser
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { 
    http_response_code(200); 
    exit(); 
}

// Koneksi Database
$host = "127.0.0.1"; $port = "3307"; $user = "root"; $pass = ""; $db = "ai_caregiver_baru";
$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) { 
    die(json_encode(["success" => false, "message" => "Koneksi DB Gagal"])); 
}

$data = json_decode(file_get_contents("php://input"), true);
$action = $data['action'] ?? '';

if ($action === 'register') {
    $nama = $data['nama'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $q = $data['pertanyaan'];
    $a = strtolower(trim($data['jawaban']));

    $cek = $conn->prepare("SELECT id FROM users WHERE nama = ?");
    $cek->bind_param("s", $nama);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Nama sudah terdaftar!"]); exit;
    }

    $stmt = $conn->prepare("INSERT INTO users (nama, password, role, pertanyaan_keamanan, jawaban_keamanan) VALUES (?, ?, 'caregiver', ?, ?)");
    $stmt->bind_param("ssss", $nama, $password, $q, $a);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Pendaftaran berhasil!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal mendaftar."]);
    }
} 
elseif ($action === 'login') {
    $stmt = $conn->prepare("SELECT password FROM users WHERE nama = ?");
    $stmt->bind_param("s", $data['nama']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($data['password'], $row['password'])) {
            echo json_encode(["success" => true, "nama" => $data['nama']]);
        } else {
            echo json_encode(["success" => false, "message" => "Password salah!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Akun tidak ditemukan"]);
    }
}
elseif ($action === 'check_question') {
    $stmt = $conn->prepare("SELECT pertanyaan_keamanan FROM users WHERE nama = ?");
    $stmt->bind_param("s", $data['nama']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode(["success" => true, "pertanyaan" => $row['pertanyaan_keamanan']]);
    } else {
        echo json_encode(["success" => false, "message" => "Akun tidak ditemukan"]);
    }
} 
elseif ($action === 'reset_password') {
    $stmt = $conn->prepare("SELECT jawaban_keamanan FROM users WHERE nama = ?");
    $stmt->bind_param("s", $data['nama']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (strtolower(trim($row['jawaban_keamanan'])) === strtolower(trim($data['jawaban']))) {
            $newPass = password_hash($data['password_baru'], PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE users SET password = ? WHERE nama = ?");
            $update->bind_param("ss", $newPass, $data['nama']);
            $update->execute();
            echo json_encode(["success" => true, "message" => "Password diubah!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Jawaban salah!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Akun tidak ditemukan"]);
    }
}
?>