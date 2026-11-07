<?php
// api/jurnal.php
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
    // Ambil data jurnal beserta nama pasien
    $sql = "SELECT j.*, p.nama as nama_pasien FROM journals j 
            JOIN patients p ON j.patient_id = p.id 
            ORDER BY j.id DESC";
    $result = $conn->query($sql);
    $journals = [];
    while($row = $result->fetch_assoc()) { $journals[] = $row; }
    echo json_encode(["success" => true, "data" => $journals]);

} elseif ($method === 'POST') {
    // Tambah data jurnal
    $stmt = $conn->prepare("INSERT INTO journals (patient_id, tanggal, aktivitas, catatan) VALUES (?, ?, ?, ?)");
    
    $stmt->bind_param("isss", 
        $data['patient_id'], 
        $data['tanggal'], 
        $data['aktivitas'], 
        $data['catatan']
    );
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Jurnal berhasil disimpan"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menyimpan: " . $stmt->error]);
    }
}
?>