<?php
header("Content-Type: application/json");

// Konfigurasi database
$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "gereja_gembala_baik";

$conn = mysqli_connect($server_name, $username, $password, $database_name);

// Cek koneksi database
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
    $table_name = isset($_POST['table_name']) ? $_POST['table_name'] : null;
    $id_name = isset($_POST['id_name']) ? $_POST['id_name'] : null;

    $sql = "DELETE FROM $table_name WHERE $id_name = $id";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI HAPUS!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
