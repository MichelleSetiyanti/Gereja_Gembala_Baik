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

// Cek metode request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Ambil data dari request
    $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
    // id_admin buat akun admin yg mana yang login dan ngubah data
    // $id_admin = isset($_POST['id_admin']) ? (int)$_POST['id_admin'] : null;

    $nama_lengkap = mysqli_real_escape_string($conn, $_POST['nama_pastor']);

    $sql = "UPDATE pastor_paroki SET nama_pastor = '$nama_lengkap' WHERE id = $id";

    error_log(print_r($_POST, true)); // Log semua data yang diterima

    if (mysqli_query($conn, $sql)) {
        // updateUserStatus($id_admin, 1, 2);
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI UPDATE!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
