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
    $nama_ayah = mysqli_real_escape_string($conn, $_POST['nama_ayah']);
    $nama_ibu = mysqli_real_escape_string($conn, $_POST['nama_ibu']);
    $sd_kelas = mysqli_real_escape_string($conn, $_POST['sd_kelas']);
    $nama_sekolah = mysqli_real_escape_string($conn, $_POST['nama_sekolah']);
    $tanggal_katekumen = mysqli_real_escape_string($conn, $_POST['tanggal_katekumen']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $detail_alamat = mysqli_real_escape_string($conn, $_POST['detail_alamat']);
    $no_telp = mysqli_real_escape_string($conn, $_POST['no_telp']);
    $id_admin = mysqli_real_escape_string($conn, $_POST['id_admin']);

    $sql = "INSERT INTO katekumen_anak
            (id_admin, nik , nama_lengkap, nama_ayah, nama_ibu, sd_kelas, nama_sekolah, tanggal_katekumen, alamat, detail_alamat, no_telp) 
            VALUES 
            ('$id_admin','$nik', '$nama_lengkap', '$nama_ayah', '$nama_ibu', '$sd_kelas', '$nama_sekolah', '$tanggal_katekumen', '$alamat', '$detail_alamat', '$no_telp')";

    if (mysqli_query($conn, $sql)) {
        updateUserStatus($id_admin, 1, 2);
        echo json_encode(["success" => true, "message" => "DATA BERHASIL DI SIMPAN!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error: " . mysqli_error($conn)]);
    }

    mysqli_close($conn);
}
