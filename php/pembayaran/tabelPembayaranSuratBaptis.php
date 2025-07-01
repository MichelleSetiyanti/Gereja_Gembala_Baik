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

if (isset($_GET["admin_id"])) {
    $sql = "SELECT * FROM pembayaran_surat_baptis WHERE id_admin = " . $_GET["admin_id"];
} else {
    $sql = "SELECT * FROM pembayaran_surat_baptis";
}
$query = mysqli_query($conn, $sql);

$data = [];

while ($surat = mysqli_fetch_assoc($query)) {
    $data[] = $surat;
}

echo json_encode(["success" => true, "data" => $data]);
