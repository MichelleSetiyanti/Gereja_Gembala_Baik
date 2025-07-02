<?php
header("Content-Type: application/json");
$server_name = "localhost";  // Sesuaikan dengan konfigurasi server Anda
$username = "root";          // Sesuaikan
$password = "";              // Sesuaikan
$database_name = "gereja_gembala_baik"; // Sesuaikan

$conn = mysqli_connect($server_name, $username, $password, $database_name);

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}

$reportYear = $_GET["year"] ?? date("Y"); // Ambil tahun laporan dari parameter GET, default ke tahun saat ini    

$sql = "SELECT
    sb.created_at,
    sb.id_admin,
    sb.nik, 
       sb.nama_lengkap, 
       sb.nama_baptis, 
       sb.nama_ayah, 
       sb.nama_ibu,
       CASE 
           WHEN EXISTS (SELECT 1 FROM data_biduk db WHERE db.nik = sb.nik) 
           THEN 'Terdaftar Di Biduk'
           ELSE 'Tidak Terdaftar Di Biduk' 
       END AS is_exist
FROM surat_baptis sb WHERE sb.created_at BETWEEN CONCAT($reportYear, '-01-01') AND CONCAT($reportYear, '-12-31 23:59:59') ORDER BY sb.created_at DESC";

$result = mysqli_query($conn, $sql);

// Check if the query failed
if (!$result) {
    echo json_encode(["success" => false, "message" => "Query failed: " . mysqli_error($conn)]);
    exit;
}

$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode(["success" => true, "data" => $data]);
