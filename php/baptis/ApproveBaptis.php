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
    $status = $_POST['status'] ?? null;
    $id = $_POST['id'] ?? null;


    error_log(print_r($_POST, true)); // Log semua data yang diterima

    // Validasi apakah semua data ada
    if (!$status) {
        echo json_encode(["success" => false, "message" => "Semua data harus diisi!"]);
        exit;
    }

    $sql = "UPDATE surat_baptis SET status='$status' WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        if ($status == 2) {
            echo json_encode(["success" => true, "message" => "DATA BERHASIL DI TOLAK"]);
        } else {
            echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SETUJUI"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }
}
