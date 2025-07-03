<?php
include "../upload_file.php";
include '../user/update_user_status.php';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pastor = mysqli_real_escape_string($conn, $_POST['nama_pastor']);

    $sql = "INSERT INTO pastor_paroki(nama_pastor) VALUES ('$nama_pastor')";

    if (mysqli_query($conn, $sql)) {
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
