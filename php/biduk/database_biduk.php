<?php
header('Content-Type: application/json');

$server_name = "localhost";
$username = "root";
$password = "";
$database_name = "gereja_gembala_baik";


$conn = mysqli_connect($server_name, $username, $password, $database_name);


if (!$conn) {
    echo json_encode(["success" => false, "message" => "Koneksi database gagal!"]);
    exit;
}


// Cek metode request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // id_admin buat akun admin yg mana yang login dan ngubah data
    // $id_admin = isset($_POST['id_admin']) ? (int)$_POST['id_admin'] : null;
    $nik = $_POST['nik'] ?? null;
    $nama_keluarga = $_POST['nama_keluarga'] ?? null;
    $alamat = $_POST['alamat'] ?? null;
    $id_admin = $_POST['id_admin'] ?? null;

    error_log(print_r($_POST, true)); // Log semua data yang diterima

    $sql = "INSERT INTO data_biduk (nik, nama_keluarga, alamat, id_admin) VALUES ('$nik', '$nama_keluarga','$alamat','$id_admin')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }
}
