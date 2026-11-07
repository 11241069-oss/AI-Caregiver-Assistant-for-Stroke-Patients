<?php
// api/observasi.php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit(); }

$host = "127.0.0.1";
$port = "3307";
$user = "root";
$pass = "";
$db   = "ai_caregiver_baru";

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi DB Gagal: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

if ($method === 'GET') {
    // Ambil data observasi beserta nama pasien
    $sql = "SELECT o.*, p.nama as nama_pasien FROM observations o 
            JOIN patients p ON o.patient_id = p.id 
            ORDER BY o.id DESC";
    $result = $conn->query($sql);
    $observations = [];
    while($row = $result->fetch_assoc()) { $observations[] = $row; }
    echo json_encode(["success" => true, "data" => $observations]);

} elseif ($method === 'POST') {
    // Tambah data observasi
    $stmt = $conn->prepare("INSERT INTO observations (patient_id, tanggal, tekanan_darah, gula_darah, gejala_klinis, tingkat_perhatian, catatan) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("issssss", 
        $data['patient_id'], 
        $data['tanggal'], 
        $data['tekanan_darah'], 
        $data['gula_darah'], 
        $data['gejala_klinis'], 
        $data['tingkat_perhatian'], 
        $data['catatan']
    );
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Observasi berhasil disimpan"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menyimpan: " . $stmt->error]);
    }
}
?>