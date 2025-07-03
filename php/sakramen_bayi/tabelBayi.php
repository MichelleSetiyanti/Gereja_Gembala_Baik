<?php
header("Content-Type: application/json");
$server_name = "localhost";  // Sesuaikan dengan konfigurasi server Anda
$username = "root";          // Sesuaikan
$password = "gapura";              // Sesuaikan
$database_name = "gereja_gembala_baik"; // Sesuaikan

$conn = mysqli_connect($server_name, $username, $password, $database_name);

if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}

if (isset($_GET["admin_id"])) {
    $admin_id = $_GET["admin_id"];
    $sql = "SELECT * FROM sakramen WHERE id_admin = '$admin_id' AND type='bayi'";
} else {
    $sql = "SELECT * FROM sakramen WHERE type='bayi'";
}

$query = mysqli_query($conn, $sql);

$data = [];

while ($bayi = mysqli_fetch_assoc($query)) {
    $data[] = $bayi;
}

echo json_encode(["success" => true, "data" => $data]);
