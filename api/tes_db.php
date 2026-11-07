<?php
$host = "127.0.0.1"; 
$port = "3307"; 
$user = "root"; 
$pass = ""; 
$db = "ai_caregiver_baru";

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) { 
    die("KONEKSI GAGAL: " . $conn->connect_error); 
}
echo "KONEKSI SUKSES!";