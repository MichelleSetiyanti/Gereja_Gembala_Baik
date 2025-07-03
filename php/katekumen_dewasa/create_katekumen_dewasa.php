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
    $tempat_lahir = mysqli_real_escape_string($conn, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($conn, $_POST['tanggal_lahir']);
    $nama_ayah = mysqli_real_escape_string($conn, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($conn, $_POST['nama_ibu']);
    $nama_gereja = mysqli_real_escape_string($conn, $_POST['nama_gereja']);
    $nama_suami_istri = isset($_POST['nama_suami_istri']) && $_POST['nama_suami_istri'] !== ''
        ? "'" . mysqli_real_escape_string($conn, $_POST['nama_suami_istri']) . "'"
        : "NULL";
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $id_admin = mysqli_real_escape_string($conn, $_POST['id_admin']);

    $sql = "INSERT INTO katekumen_dewasa
            (id_admin,nik, nama_lengkap, tempat_lahir, tanggal_lahir, nama_ayah, nama_ibu, nama_gereja,
            nama_suami_istri, alamat, no_telp) 
            VALUES 
            ('$id_admin','$nik', '$nama_lengkap', '$tempat_lahir', '$tanggal_lahir', '$nama_ayah', '$nama_ibu', '$nama_gereja',
            $nama_suami_istri, '$alamat', '$no_telp')";

    if (mysqli_query($conn, $sql)) {
        updateUserStatus($id_admin, 1, 2);

        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
