<?php
// api/progres.php
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
    // Ambil data progres beserta nama pasien
    $sql = "SELECT pr.*, p.nama as nama_pasien FROM progress pr 
            JOIN patients p ON pr.patient_id = p.id 
            ORDER BY pr.id DESC";
    $result = $conn->query($sql);
    $progress = [];
    while($row = $result->fetch_assoc()) { $progress[] = $row; }
    echo json_encode(["success" => true, "data" => $progress]);

} elseif ($method === 'POST') {
    // Tambah data progres
    $stmt = $conn->prepare("INSERT INTO progress (patient_id, tanggal, skor, catatan) VALUES (?, ?, ?, ?)");
    
    $stmt->bind_param("isis", 
        $data['patient_id'], 
        $data['tanggal'], 
        $data['skor'], 
        $data['catatan']
    );
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Progres berhasil disimpan"]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menyimpan: " . $stmt->error]);
    }
}
?>