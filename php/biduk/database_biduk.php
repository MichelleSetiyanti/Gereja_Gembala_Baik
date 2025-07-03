<?php
header('Content-Type: application/json');

$server_name = "localhost";
$username = "root";
$password = "gapura";
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
    $alamat = $_POST['alamat'] ?? null;
    $id_admin = $_POST['id_admin'] ?? null;
    $nama_ayah = $_POST['nama_ayah'] ?? null;
    $nama_ibu = $_POST['nama_ibu'] ?? null;
    $lm_paroki = $_POST['lm_paroki'] ?? null;

    error_log(print_r($_POST, true)); // Log semua data yang diterima

    $sql = "INSERT INTO data_biduk (nik,  alamat, id_admin, nama_ayah, nama_ibu, lm_paroki) VALUES ('$nik','$alamat','$id_admin', '$nama_ayah', '$nama_ibu', '$lm_paroki')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }
}
