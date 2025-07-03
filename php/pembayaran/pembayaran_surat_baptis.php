<?php
include "../upload_file.php";
include '../user/update_user_status.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
    $tanggal_baptis = mysqli_real_escape_string($conn, $_POST['tanggal_baptis']);


    $sql = "INSERT INTO pembayaran_surat_baptis(nik, nama_lengkap, tanggal_baptis) VALUES ('$nik', '$nama_lengkap','$tanggal_baptis')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
